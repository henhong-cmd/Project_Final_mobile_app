class Course {
  final int id;
  final String name;
  final String? code;
  final String? description;
  final String? duration;
  final int? credits;
  final double? price;

  const Course({
    required this.id,
    required this.name,
    this.code,
    this.description,
    this.duration,
    this.credits,
    this.price,
  });

  factory Course.fromJson(Map<String, dynamic> json) => Course(
    id: json['id'] as int,
    name: json['name'] as String,
    code: json['code'] as String?,
    description: json['description'] as String?,
    duration: json['duration'] as String?,
    credits: int.tryParse(json['credits']?.toString() ?? ''),
    price: double.tryParse(json['price']?.toString() ?? ''),
  );

  Map<String, dynamic> toJson() => {
    'id': id,
    'code': code,
    'name': name,
    'description': description,
    'duration': duration,
    'credits': credits,
    'price': price,
  };
}
