import 'package:flutter/material.dart';
import '../models/student_model.dart';
import '../services/student_service.dart';

class StudentAddScreen extends StatefulWidget {
  const StudentAddScreen({super.key});

  @override
  State<StudentAddScreen> createState() => _StudentAddScreenState();
}

class _StudentAddScreenState extends State<StudentAddScreen> {
  @override
  Widget build(BuildContext context) {
    return Scaffold(
      appBar: AppBar(title: Text("Add New Student")),
      body: _buildBodyWithLoading(),
    );
  }

  bool _loading = false;

  Widget _buildBodyWithLoading() {
    return Stack(children: [_buildBody(), if (_loading) _buildLoadingCover()]);
  }

  Widget _buildLoadingCover() {
    return Container(
      color: Colors.black38,
      alignment: Alignment.center,
      child: CircularProgressIndicator(),
    );
  }

  final _formKey = GlobalKey<FormState>();

  Widget _buildBody() {
    return Form(
      key: _formKey,
      child: ListView(
        padding: const EdgeInsets.all(8),
        children: [_buildFullNameTextField(), _buildSaveButton()],
      ),
    );
  }

  final _fullNameCtrl = TextEditingController();

  @override
  void dispose() {
    _fullNameCtrl.dispose();
    super.dispose();
  }

  Widget _buildFullNameTextField() {
    return TextFormField(
      controller: _fullNameCtrl,
      decoration: InputDecoration(
        border: OutlineInputBorder(),
        prefixIcon: Icon(Icons.person),
        hintText: "Full Name",
      ),
      validator: (text) {
        if (text!.isEmpty) {
          return "Full Name is required";
        }
        return null; // no error, return null
      },
    );
  }

  final _service = StudentService();

  Widget _buildSaveButton() {
    return FilledButton.icon(
      onPressed: _loading ? null : _save,
      icon: Icon(Icons.save_alt),
      label: Text("SAVE"),
    );
  }

  Future<void> _save() async {
    if (!_formKey.currentState!.validate()) return;
    setState(() => _loading = true);
    try {
      await _service.create(
        StudentModel(id: 0, fullName: _fullNameCtrl.text.trim()),
      );
      if (mounted) Navigator.pop(context, true);
    } catch (error) {
      if (mounted) {
        ScaffoldMessenger.of(context).showSnackBar(
          SnackBar(content: Text('Could not save student: $error')),
        );
      }
    } finally {
      if (mounted) setState(() => _loading = false);
    }
  }
}
