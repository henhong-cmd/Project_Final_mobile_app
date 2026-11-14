import 'package:flutter/material.dart';
import 'package:flutter_riverpod/flutter_riverpod.dart';
import '../providers/color_notifier_provider.dart';
import '../providers/theme_notifier_provider.dart';
import '../screens/main_screen.dart';
import '../screens/student_add_screen.dart';

List<Color> colorList = [
  Colors.yellow,
  Colors.lime,
  Colors.green,
  Colors.orange,
  Colors.deepOrange,
  Colors.pinkAccent,
  Colors.pink,
  Colors.red,
  Colors.deepPurple,
  Colors.cyanAccent,
  Colors.cyan,
  Colors.lightBlue,
  Colors.blue,
  Colors.indigo,
  Colors.grey,
];

class MyApp extends ConsumerWidget {
  const MyApp({super.key});

  @override
  Widget build(BuildContext context, WidgetRef ref) {
    bool dark = ref.watch(themeNotifierProvider);
    int index = ref.watch(colorNotifierProvider);
    Color seedColor = colorList[index];

    return MaterialApp(
      debugShowCheckedModeBanner: false,
      themeMode: dark ? ThemeMode.dark : ThemeMode.light,
      theme: ThemeData(
        brightness: Brightness.light,
        colorScheme: ColorScheme.fromSeed(
          seedColor: seedColor,
          brightness: Brightness.light,
        ),
      ),
      darkTheme: ThemeData(
        brightness: Brightness.dark,
        colorScheme: ColorScheme.fromSeed(
          seedColor: seedColor,
          brightness: Brightness.dark,
        ),
      ),
      home: const MainScreen(),
      onGenerateRoute: (settings) {
        switch (settings.name) {
          case '/':
            return MaterialPageRoute(builder: (context) => const MainScreen());
          case '/home':
            return MaterialPageRoute(builder: (context) => const MainScreen());
          case '/student-add':
            return MaterialPageRoute(
              builder: (context) => const StudentAddScreen(),
            );
          default:
            return MaterialPageRoute(
              builder: (context) => Text("No Screen Found"),
            );
        }
      },
    );
  }
}
