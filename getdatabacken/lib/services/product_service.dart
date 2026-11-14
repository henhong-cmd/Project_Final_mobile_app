import 'dart:convert';

import 'package:http/http.dart' as http;

import '../models/product_model.dart';
import 'auth_service.dart';
import 'base_url.dart';

class ProductService {
  final String _baseUrl = BaseURL.base;
  final AuthService _authService = AuthService();

  Future<List<Product>> fetch() async {
    final response = await http
        .get(Uri.parse('$_baseUrl/api/products'), headers: await _headers())
        .timeout(const Duration(seconds: 10));
    if (response.statusCode != 200) {
      throw Exception('Server error: ${response.statusCode}');
    }

    final data = jsonDecode(response.body) as List<dynamic>;
    return data
        .map((item) => Product.fromJson(item as Map<String, dynamic>))
        .toList();
  }

  Future<void> create(Product product) async {
    final response = await http
        .post(
          Uri.parse('$_baseUrl/api/products'),
          headers: await _headers(json: true),
          body: jsonEncode(product.toJson()),
        )
        .timeout(const Duration(seconds: 10));
    if (response.statusCode != 201) {
      throw Exception('Server error: ${response.statusCode}');
    }
  }

  Future<Map<String, String>> _headers({bool json = false}) async {
    final token = await _authService.token();
    return {
      'Accept': 'application/json',
      if (json) 'Content-Type': 'application/json',
      if (token != null && token.isNotEmpty) 'Authorization': 'Bearer $token',
    };
  }
}
