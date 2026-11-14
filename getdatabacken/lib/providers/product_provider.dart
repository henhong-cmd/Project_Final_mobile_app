import 'dart:async';

import 'package:flutter_riverpod/flutter_riverpod.dart';

import '../models/product_model.dart';
import '../services/product_service.dart';

final productServiceProvider = Provider<ProductService>((ref) {
  return ProductService();
});

class ProductNotifier extends AsyncNotifier<List<Product>> {
  @override
  FutureOr<List<Product>> build() {
    return ref.read(productServiceProvider).fetch();
  }

  Future<void> refresh() async {
    state = const AsyncLoading();
    state = await AsyncValue.guard(
      () => ref.read(productServiceProvider).fetch(),
    );
  }
}

final productNotifierProvider =
    AsyncNotifierProvider<ProductNotifier, List<Product>>(ProductNotifier.new);
