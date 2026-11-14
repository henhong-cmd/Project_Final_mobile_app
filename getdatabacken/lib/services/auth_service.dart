import 'dart:convert';

import 'package:flutter_secure_storage/flutter_secure_storage.dart';
import 'package:http/http.dart' as http;

import 'base_url.dart';

class AuthService {
  static const _tokenKey = 'auth_token';
  final FlutterSecureStorage _storage = const FlutterSecureStorage();

  Future<void> login(String email, String password) async {
    final response = await http.post(
      Uri.parse('${BaseURL.base}/api/login'),
      headers: const {
        'Accept': 'application/json',
        'Content-Type': 'application/json',
      },
      body: jsonEncode({'email': email, 'password': password}),
    );
    if (response.statusCode != 200) {
      final body = jsonDecode(response.body) as Map<String, dynamic>;
      throw Exception(body['message'] ?? 'Login failed.');
    }
    final body = jsonDecode(response.body) as Map<String, dynamic>;
    await _storage.write(key: _tokenKey, value: body['token'] as String);
  }

  Future<String?> token() => _storage.read(key: _tokenKey);

  Future<void> logout() async {
    final token = await this.token();
    if (token != null && token.isNotEmpty) {
      await http.post(
        Uri.parse('${BaseURL.base}/api/logout'),
        headers: {
          'Accept': 'application/json',
          'Authorization': 'Bearer $token',
        },
      );
    }
    await _storage.delete(key: _tokenKey);
  }
}
