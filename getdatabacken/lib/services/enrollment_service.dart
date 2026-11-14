import 'dart:convert';

import 'package:http/http.dart' as http;

import '../models/enrollment_model.dart';
import 'base_url.dart';

class EnrollmentService {
  Future<List<Enrollment>> fetch() async {
    final response = await http
        .get(
          Uri.parse('${BaseURL.base}/api/enrollments'),
          headers: const {'Accept': 'application/json'},
        )
        .timeout(const Duration(seconds: 10));

    if (response.statusCode != 200) {
      throw Exception(
        'Could not load enrollments (${response.statusCode}): ${response.body}',
      );
    }

    final data = jsonDecode(response.body) as List<dynamic>;

    return data
        .map((item) => Enrollment.fromJson(item as Map<String, dynamic>))
        .toList();
  }
}
