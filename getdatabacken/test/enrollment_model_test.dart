import 'package:flutter_test/flutter_test.dart';
import 'package:getdatabacken/models/enrollment_model.dart';

void main() {
  test('parses a nested enrollment API response', () {
    final enrollment = Enrollment.fromJson({
      'id': 1,
      'student_id': 4,
      'course_id': 1,
      'enrolled_at': '2026-11-10T00:00:00.000000Z',
      'status': 'active',
      'student': {'id': 4, 'full_name': 'San Samnang'},
      'course': {'id': 1, 'name': 'Python programming'},
    });

    expect(enrollment.id, 1);
    expect(enrollment.studentName, 'San Samnang');
    expect(enrollment.courseName, 'Python programming');
    expect(enrollment.status, 'active');
    expect(enrollment.enrolledAt, DateTime.parse('2026-11-10T00:00:00Z'));
  });

  test('parses enrollment responses without optional status or date', () {
    final enrollment = Enrollment.fromJson({
      'id': 2,
      'student': {'full_name': 'Alex Student'},
      'course': {'name': 'Dart Fundamentals'},
    });

    expect(enrollment.studentName, 'Alex Student');
    expect(enrollment.courseName, 'Dart Fundamentals');
    expect(enrollment.status, 'active');
    expect(enrollment.enrolledAt, isNull);
  });
}
