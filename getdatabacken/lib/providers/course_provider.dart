import 'dart:async';

import 'package:flutter_riverpod/flutter_riverpod.dart';

import '../models/course_model.dart';
import '../services/course_service.dart';

final courseServiceProvider = Provider<CourseService>((ref) {
  return CourseService();
});

class CourseNotifier extends AsyncNotifier<List<Course>> {
  @override
  FutureOr<List<Course>> build() {
    return ref.read(courseServiceProvider).fetchCourses();
  }

  Future<void> refresh() async {
    state = const AsyncLoading();
    state = await AsyncValue.guard(
      () => ref.read(courseServiceProvider).fetchCourses(),
    );
  }
}

final courseNotifierProvider =
    AsyncNotifierProvider<CourseNotifier, List<Course>>(CourseNotifier.new);
