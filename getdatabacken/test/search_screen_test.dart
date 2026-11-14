import 'package:flutter/material.dart';
import 'package:flutter_riverpod/flutter_riverpod.dart';
import 'package:flutter_test/flutter_test.dart';
import 'package:getdatabacken/models/course_model.dart';
import 'package:getdatabacken/providers/course_provider.dart';
import 'package:getdatabacken/screens/search_screen.dart';
import 'package:getdatabacken/services/course_service.dart';

class _FakeCourseService extends CourseService {
  @override
  Future<List<Course>> fetchCourses() async => const [
    Course(
      id: 1,
      name: 'Flutter Basics',
      code: 'FL-101',
      description: 'Build mobile apps',
      duration: '6 weeks',
      price: 99,
    ),
    Course(
      id: 2,
      name: 'Dart Advanced',
      code: 'DA-201',
      description: 'Advanced Dart programming',
      duration: '4 weeks',
      price: 120,
    ),
  ];
}

void main() {
  testWidgets('search filters courses by their details', (tester) async {
    await tester.pumpWidget(
      ProviderScope(
        overrides: [
          courseServiceProvider.overrideWithValue(_FakeCourseService()),
        ],
        child: const MaterialApp(home: SearchScreen()),
      ),
    );
    await tester.pumpAndSettle();

    expect(find.text('Flutter Basics'), findsOneWidget);
    expect(find.text('Dart Advanced'), findsOneWidget);

    await tester.enterText(find.byType(TextField), 'DA-201');
    await tester.pumpAndSettle();

    expect(find.text('Flutter Basics'), findsNothing);
    expect(find.text('Dart Advanced'), findsOneWidget);
  });
}
