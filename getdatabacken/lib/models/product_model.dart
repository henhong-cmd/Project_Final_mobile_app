class Product {
  final int id;
  final String name;
  final String sku;
  final double price;
  final int stock;

  const Product({
    required this.id,
    required this.name,
    required this.sku,
    required this.price,
    required this.stock,
  });

  factory Product.fromJson(Map<String, dynamic> json) => Product(
    id: json['id'] as int,
    name: json['name'] as String,
    sku: json['sku'] as String,
    price: double.parse(json['price'].toString()),
    stock: json['stock'] as int,
  );

  Map<String, dynamic> toJson() => {
    'name': name,
    'sku': sku,
    'price': price,
    'stock': stock,
  };
}
