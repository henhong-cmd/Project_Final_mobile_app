import 'dart:convert';

import 'package:http/http.dart' as http;

import '../models/course_model.dart';
import 'base_url.dart';

class CourseService {
  final String _baseUrl = BaseURL.base;

  Future<List<Course>> fetchCourses() async {
    final response = await http
        .get(
          Uri.parse('$_baseUrl/api/courses'),
          headers: const {'Accept': 'application/json'},
        )
        .timeout(const Duration(seconds: 10));
    if (response.statusCode != 200) {
      throw Exception(
        'Could not load courses (${response.statusCode}): ${response.body}',
      );
    }

    final data = jsonDecode(response.body) as List<dynamic>;
    return data
        .map((item) => Course.fromJson(item as Map<String, dynamic>))
        .toList();
  }

  Future<void> enroll({required int courseId, required int studentId}) async {
    final today = DateTime.now();
    final enrolledAt =
        '${today.year.toString().padLeft(4, '0')}-'
        '${today.month.toString().padLeft(2, '0')}-'
        '${today.day.toString().padLeft(2, '0')}';

    final response = await http
        .post(
          Uri.parse('$_baseUrl/api/enrollments'),
          headers: const {
            'Accept': 'application/json',
            'Content-Type': 'application/json',
          },
          body: jsonEncode({
            'course_id': courseId,
            'student_id': studentId,
            'enrolled_at': enrolledAt,
            'status': 'active',
          }),
        )
        .timeout(const Duration(seconds: 10));
    if (response.statusCode != 201) {
      if (response.statusCode == 422) {
        try {
          final responseData = jsonDecode(response.body);
          if (responseData is Map<String, dynamic>) {
            final errors = responseData['errors'];
            if (errors is Map<String, dynamic>) {
              for (final messages in errors.values) {
                if (messages is List &&
                    messages.isNotEmpty &&
                    messages.first is String) {
                  throw Exception(messages.first as String);
                }
              }
            }
          }
        } on FormatException {
          // Use the full response below when the server does not return JSON.
        }
      }

      throw Exception(
        'Could not enroll student (${response.statusCode}): ${response.body}',
      );
    }
  }
}
