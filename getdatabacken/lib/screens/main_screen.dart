import 'package:flutter/material.dart';
import 'package:persistent_bottom_nav_bar_v2/persistent_bottom_nav_bar_v2.dart';

import 'course_screen.dart';
import 'enrollment_screen.dart';
import 'search_screen.dart';
import 'settings_screen.dart';

class MainScreen extends StatefulWidget {
  const MainScreen({super.key});

  @override
  State<MainScreen> createState() => _MainScreenState();
}

class _MainScreenState extends State<MainScreen> {
  @override
  Widget build(BuildContext context) {
    return PersistentTabView(
      tabs: [
        PersistentTabConfig(
          screen: const CourseScreen(),
          item: ItemConfig(
            icon: Icon(Icons.school),
            title: "Courses",
            activeForegroundColor: Theme.of(
              context,
            ).colorScheme.onSecondaryContainer,
            inactiveForegroundColor: Theme.of(context).colorScheme.onPrimary,
          ),
        ),
        PersistentTabConfig(
          screen: const SearchScreen(),
          item: ItemConfig(
            icon: Icon(Icons.search),
            title: "Search",
            activeForegroundColor: Theme.of(
              context,
            ).colorScheme.onSecondaryContainer,
            inactiveForegroundColor: Theme.of(context).colorScheme.onPrimary,
          ),
        ),

        PersistentTabConfig(
          screen: const EnrollmentScreen(),
          item: ItemConfig(
            icon: Icon(Icons.fact_check),
            title: "Enrollments",
            activeForegroundColor: Theme.of(
              context,
            ).colorScheme.onSecondaryContainer,
            inactiveForegroundColor: Theme.of(context).colorScheme.onPrimary,
          ),
        ),

        PersistentTabConfig(
          screen: SettingsScreen(),
          item: ItemConfig(
            icon: Icon(Icons.settings),
            title: "Settings",
            activeForegroundColor: Theme.of(
              context,
            ).colorScheme.onSecondaryContainer,
            inactiveForegroundColor: Theme.of(context).colorScheme.onPrimary,
          ),
        ),
      ],
      navBarBuilder: (navBarConfig) => Style9BottomNavBar(
        navBarConfig: navBarConfig,
        navBarDecoration: NavBarDecoration(
          color: Theme.of(context).colorScheme.secondaryContainer,
        ),
      ),
    );
  }
}
