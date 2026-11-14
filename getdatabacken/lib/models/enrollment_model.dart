class Enrollment {
  final int id;
  final String studentName;
  final String courseName;
  final String status;
  final DateTime? enrolledAt;

  const Enrollment({
    required this.id,
    required this.studentName,
    required this.courseName,
    required this.status,
    required this.enrolledAt,
  });

  factory Enrollment.fromJson(Map<String, dynamic> json) {
    final student = json['student'] as Map<String, dynamic>?;
    final course = json['course'] as Map<String, dynamic>?;

    return Enrollment(
      id: json['id'] as int,
      studentName: student?['full_name'] as String? ?? 'Unknown student',
      courseName: course?['name'] as String? ?? 'Unknown course',
      status: json['status'] as String? ?? 'active',
      enrolledAt: DateTime.tryParse(
        (json['enrolled_at'] ?? json['created_at'])?.toString() ?? '',
      ),
    );
  }
}
