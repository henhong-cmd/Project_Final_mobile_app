import 'dart:async';

import 'package:flutter_riverpod/flutter_riverpod.dart';

import '../models/enrollment_model.dart';
import '../services/enrollment_service.dart';

final enrollmentServiceProvider = Provider<EnrollmentService>((ref) {
  return EnrollmentService();
});

class EnrollmentNotifier extends AsyncNotifier<List<Enrollment>> {
  @override
  FutureOr<List<Enrollment>> build() {
    return ref.read(enrollmentServiceProvider).fetch();
  }

  Future<void> refresh() async {
    state = const AsyncLoading();
    state = await AsyncValue.guard(
      () => ref.read(enrollmentServiceProvider).fetch(),
    );
  }
}

final enrollmentNotifierProvider =
    AsyncNotifierProvider<EnrollmentNotifier, List<Enrollment>>(
      EnrollmentNotifier.new,
    );
