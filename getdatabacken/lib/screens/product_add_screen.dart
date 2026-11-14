import 'package:flutter/material.dart';
import 'package:flutter_riverpod/flutter_riverpod.dart';

import '../models/product_model.dart';
import '../providers/product_provider.dart';

class ProductAddScreen extends ConsumerStatefulWidget {
  const ProductAddScreen({super.key});

  @override
  ConsumerState<ProductAddScreen> createState() => _ProductAddScreenState();
}

class _ProductAddScreenState extends ConsumerState<ProductAddScreen> {
  final _formKey = GlobalKey<FormState>();
  final _name = TextEditingController();
  final _sku = TextEditingController();
  final _price = TextEditingController();
  final _stock = TextEditingController();
  bool _saving = false;

  @override
  void dispose() {
    _name.dispose();
    _sku.dispose();
    _price.dispose();
    _stock.dispose();
    super.dispose();
  }

  Future<void> _save() async {
    if (!_formKey.currentState!.validate()) return;
    setState(() => _saving = true);
    try {
      await ref
          .read(productServiceProvider)
          .create(
            Product(
              id: 0,
              name: _name.text.trim(),
              sku: _sku.text.trim(),
              price: double.parse(
                _price.text.trim().replaceFirst(RegExp(r'^\$'), ''),
              ),
              stock: int.parse(_stock.text.trim()),
            ),
          );
      if (mounted) Navigator.pop(context);
    } catch (error) {
      if (mounted) {
        ScaffoldMessenger.of(context).showSnackBar(
          SnackBar(content: Text('Could not save product: $error')),
        );
      }
    } finally {
      if (mounted) setState(() => _saving = false);
    }
  }

  String? _required(String? value) =>
      value == null || value.trim().isEmpty ? 'Required' : null;

  String? _positiveNumber(String? value) {
    if (_required(value) != null) return 'Required';
    final number = double.tryParse(
      value!.trim().replaceFirst(RegExp(r'^\$'), ''),
    );
    return number == null || number < 0 ? 'Enter a valid number' : null;
  }

  String? _wholeNumber(String? value) {
    if (_required(value) != null) return 'Required';
    final number = int.tryParse(value!);
    return number == null || number < 0 ? 'Enter a whole number' : null;
  }

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      appBar: AppBar(title: const Text('Add Product')),
      body: Form(
        key: _formKey,
        child: ListView(
          padding: const EdgeInsets.all(16),
          children: [
            TextFormField(
              controller: _name,
              decoration: const InputDecoration(labelText: 'Product name'),
              validator: _required,
            ),
            TextFormField(
              controller: _sku,
              decoration: const InputDecoration(labelText: 'SKU'),
              validator: _required,
            ),
            TextFormField(
              controller: _price,
              decoration: const InputDecoration(labelText: 'Price'),
              keyboardType: const TextInputType.numberWithOptions(
                decimal: true,
              ),
              validator: _positiveNumber,
            ),
            TextFormField(
              controller: _stock,
              decoration: const InputDecoration(labelText: 'Stock'),
              keyboardType: TextInputType.number,
              validator: _wholeNumber,
            ),
            const SizedBox(height: 20),
            FilledButton.icon(
              onPressed: _saving ? null : _save,
              icon: const Icon(Icons.save),
              label: Text(_saving ? 'Saving...' : 'Save product'),
            ),
          ],
        ),
      ),
    );
  }
}
