import 'package:flutter/material.dart';
import 'package:flutter_riverpod/flutter_riverpod.dart';

import '../models/enrollment_model.dart';
import '../providers/enrollment_provider.dart';

class EnrollmentScreen extends ConsumerWidget {
  const EnrollmentScreen({super.key});

  @override
  Widget build(BuildContext context, WidgetRef ref) {
    final enrollments = ref.watch(enrollmentNotifierProvider);

    return Scaffold(
      appBar: AppBar(title: const Text('Enrollments')),
      body: RefreshIndicator(
        onRefresh: () =>
            ref.read(enrollmentNotifierProvider.notifier).refresh(),
        child: enrollments.when(
          loading: () => const Center(child: CircularProgressIndicator()),
          error: (error, _) => ListView(
            physics: const AlwaysScrollableScrollPhysics(),
            children: [
              const SizedBox(height: 180),
              Center(child: Text('Could not load enrollments: $error')),
            ],
          ),
          data: (items) => items.isEmpty
              ? ListView(
                  physics: const AlwaysScrollableScrollPhysics(),
                  children: const [
                    SizedBox(height: 180),
                    Center(child: Text('No enrollments yet')),
                  ],
                )
              : ListView(
                  physics: const AlwaysScrollableScrollPhysics(),
                  padding: const EdgeInsets.all(12),
                  children: [
                    Card(
                      clipBehavior: Clip.antiAlias,
                      child: SingleChildScrollView(
                        scrollDirection: Axis.horizontal,
                        child: DataTable(
                          columns: const [
                            DataColumn(label: Text('Student')),
                            DataColumn(label: Text('Course')),
                            DataColumn(label: Text('Status')),
                            DataColumn(label: Text('Enrolled at')),
                          ],
                          rows: items.map(_toRow).toList(),
                        ),
                      ),
                    ),
                  ],
                ),
        ),
      ),
    );
  }

  DataRow _toRow(Enrollment enrollment) {
    final date = enrollment.enrolledAt?.toLocal();
    final formattedDate = date == null
        ? '-'
        : '${date.year.toString().padLeft(4, '0')}-'
              '${date.month.toString().padLeft(2, '0')}-'
              '${date.day.toString().padLeft(2, '0')}';

    return DataRow(
      cells: [
        DataCell(Text(enrollment.studentName)),
        DataCell(Text(enrollment.courseName)),
        DataCell(Text(enrollment.status)),
        DataCell(Text(formattedDate)),
      ],
    );
  }
}
