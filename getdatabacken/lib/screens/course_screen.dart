import 'package:flutter/material.dart';
import 'package:flutter_riverpod/flutter_riverpod.dart';

import '../models/course_model.dart';
import '../models/student_model.dart';
import '../providers/course_provider.dart';
import '../providers/enrollment_provider.dart';
import '../services/student_service.dart';

class CourseScreen extends ConsumerWidget {
  const CourseScreen({super.key});

  Future<void> _enroll(
    BuildContext context,
    WidgetRef ref,
    Course course,
  ) async {
    List<StudentModel> students;
    try {
      students = await StudentService().fetch();
    } catch (error) {
      if (context.mounted) {
        ScaffoldMessenger.of(context).showSnackBar(
          SnackBar(content: Text('Could not load students: $error')),
        );
      }
      return;
    }

    if (!context.mounted) return;
    if (students.isEmpty) {
      ScaffoldMessenger.of(context).showSnackBar(
        const SnackBar(content: Text('Add a student before enrolling.')),
      );
      return;
    }

    final selectedStudent = await showDialog<StudentModel>(
      context: context,
      builder: (context) {
        StudentModel? selected;
        return StatefulBuilder(
          builder: (context, setDialogState) => AlertDialog(
            title: Text('Enroll in ${course.name}'),
            content: DropdownButtonFormField<StudentModel>(
              decoration: const InputDecoration(labelText: 'Student'),
              items: students
                  .map(
                    (student) => DropdownMenuItem(
                      value: student,
                      child: Text(student.fullName),
                    ),
                  )
                  .toList(),
              onChanged: (student) => setDialogState(() => selected = student),
            ),
            actions: [
              TextButton(
                onPressed: () => Navigator.pop(context),
                child: const Text('Cancel'),
              ),
              FilledButton(
                onPressed: selected == null
                    ? null
                    : () => Navigator.pop(context, selected),
                child: const Text('Enroll'),
              ),
            ],
          ),
        );
      },
    );

    if (selectedStudent == null || !context.mounted) return;
    try {
      await ref
          .read(courseServiceProvider)
          .enroll(courseId: course.id, studentId: selectedStudent.id);
      ref.invalidate(enrollmentNotifierProvider);
      if (context.mounted) {
        ScaffoldMessenger.of(context).showSnackBar(
          SnackBar(content: Text('${selectedStudent.fullName} enrolled.')),
        );
      }
    } catch (error) {
      if (context.mounted) {
        final errorMessage = error.toString().replaceFirst('Exception: ', '');
        ScaffoldMessenger.of(context).showSnackBar(
          SnackBar(content: Text('Could not enroll student: $errorMessage')),
        );
      }
    }
  }

  @override
  Widget build(BuildContext context, WidgetRef ref) {
    final courses = ref.watch(courseNotifierProvider);
    return Scaffold(
      appBar: AppBar(
        title: const Text('Courses'),
        actions: [
          IconButton(
            tooltip: 'Add student',
            onPressed: () => Navigator.pushNamed(context, '/student-add'),
            icon: const Icon(Icons.person_add),
          ),
        ],
      ),
      body: RefreshIndicator(
        onRefresh: () => ref.read(courseNotifierProvider.notifier).refresh(),
        child: courses.when(
          loading: () => const Center(child: CircularProgressIndicator()),
          error: (error, _) => ListView(
            physics: const AlwaysScrollableScrollPhysics(),
            children: [
              const SizedBox(height: 180),
              Center(child: Text('Could not load courses: $error')),
            ],
          ),
          data: (items) => items.isEmpty
              ? ListView(
                  physics: const AlwaysScrollableScrollPhysics(),
                  children: const [
                    SizedBox(height: 180),
                    Center(child: Text('No courses available')),
                  ],
                )
              : ListView.builder(
                  padding: const EdgeInsets.all(12),
                  itemCount: items.length,
                  itemBuilder: (context, index) {
                    final course = items[index];
                    return Card(
                      child: Padding(
                        padding: const EdgeInsets.all(16),
                        child: Column(
                          crossAxisAlignment: CrossAxisAlignment.start,
                          children: [
                            Text(
                              course.name,
                              style: Theme.of(context).textTheme.titleLarge,
                            ),
                            if (course.code != null)
                              Text('Code: ${course.code}'),
                            if (course.description?.isNotEmpty ?? false) ...[
                              const SizedBox(height: 8),
                              Text(course.description!),
                            ],
                            if (course.duration != null)
                              Text('Duration: ${course.duration}'),
                            if (course.credits != null)
                              Text('Credits: ${course.credits}'),
                            if (course.price != null)
                              Text(
                                'Price: \$${course.price!.toStringAsFixed(2)}',
                              ),
                            const SizedBox(height: 8),
                            Align(
                              alignment: Alignment.centerRight,
                              child: FilledButton(
                                onPressed: () => _enroll(context, ref, course),
                                child: const Text('Enroll'),
                              ),
                            ),
                          ],
                        ),
                      ),
                    );
                  },
                ),
        ),
      ),
    );
  }
}
