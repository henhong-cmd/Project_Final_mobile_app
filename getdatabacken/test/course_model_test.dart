import 'package:flutter_test/flutter_test.dart';
import 'package:getdatabacken/models/course_model.dart';

void main() {
  group('Course.fromJson', () {
    test('parses a course from the course and enrollment API', () {
      final course = Course.fromJson({
        'id': 12,
        'code': 'CS101',
        'name': 'Introduction to Computing',
        'credits': 3,
        'students': [],
      });

      expect(course.id, 12);
      expect(course.code, 'CS101');
      expect(course.name, 'Introduction to Computing');
      expect(course.credits, 3);
      expect(course.description, isNull);
      expect(course.duration, isNull);
      expect(course.price, isNull);
    });

    test('parses optional details returned by the standalone course API', () {
      final course = Course.fromJson({
        'id': 7,
        'name': 'Flutter Development',
        'description': 'Build Flutter apps',
        'duration': '8 weeks',
        'price': '120.00',
      });

      expect(course.id, 7);
      expect(course.name, 'Flutter Development');
      expect(course.description, 'Build Flutter apps');
      expect(course.duration, '8 weeks');
      expect(course.price, 120.0);
      expect(course.credits, isNull);
    });
  });
}
