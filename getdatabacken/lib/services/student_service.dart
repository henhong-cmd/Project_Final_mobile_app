import 'package:flutter/foundation.dart';
import 'package:http/http.dart' as http;

import '../models/student_model.dart';
import 'base_url.dart';

class StudentService {
  final _baseUrl = BaseURL.base;

  Future<List<StudentModel>> fetch() async {
    final url = "$_baseUrl/api/students";

    try {
      final response = await http
          .get(Uri.parse(url))
          .timeout(const Duration(seconds: 10));
      if (response.statusCode == 200) {
        final list = await compute(studentModelFromJson, response.body);
        return list;
      }
      throw Exception('Server Error: ${response.statusCode}');
    } catch (e) {
      throw Exception('Failed to load students: $e');
    }
  }

  Future<bool> create(StudentModel item) async {
    final url = "$_baseUrl/api/students";
    final response = await http
        .post(
          Uri.parse(url),
          headers: const {
            'Accept': 'application/json',
            'Content-Type': 'application/x-www-form-urlencoded',
          },
          body: {'full_name': item.fullName},
        )
        .timeout(const Duration(seconds: 10));
    if (response.statusCode == 201) {
      return true;
    }
    throw Exception(
      'Could not save student (${response.statusCode}): ${response.body}',
    );
  }
}
