# Overview

## Introduction

This documentation aims to provide a comprehensive understanding of our Laravel application's architecture, which employs a unique approach compared to traditional Laravel starter kits. Here, we'll explore the key concepts and components that form the foundation of our application.

## Custom Authentication

The application's authentication system is built on Laravel's capabilities, tailored to fit Ignite's specific needs. Unlike predefined packages like Laravel Breeze/Fortify, we've developed a custom solution that integrates seamlessly with our application's architecture, offering flexibility and control over authentication processes.

The custom authentication setup involves routing configurations and controllers that support all standard Laravel authentication features, including login, registration, password reset, and email verification. By utilising Laravel's artisan commands, such as `route:list`, you can easily inspect the authentication routes the application uses.

#### Authentication Configuration

A dedicated configuration file (`config/auth.php`) is established, allowing for the customisation of various authentication aspects, such as guards, redirection after login, and more. This configuration file also enables the enabling or disabling of specific authentication features to suit the application's requirements.

## Actions

Differing from the approach taken by typical Laravel applications, this application doesn't publish controllers or routes directly. Instead, functionality is encapsulated within "Action" classes, which are housed in the domain specific `Actions` directory, e. g.  `src/Ignite/Auth/Actions` . These classes are responsible for executing specific tasks, like user registration or profile updates, and can be customised to adjust the application's behaviour.

## Views / Pages

The application's front-end structure is designed for flexibility, with pages located in the `resources/js/pages` directory. This setup ensures that the application is ready to be tailored to the specific needs right from the start, providing a solid foundation for building out the user interface.

### Layouts

#### The Application Layout

Our application features a primary layout used across authenticated pages, offering a consistent look and feel. This layout serves as the backbone for the user interface, ensuring a unified design across the application.

#### Guest / Authentication Layout

In addition to the main layout, a specific layout for authentication-related pages is defined, creating a distinct appearance for login, registration, and password reset pages, enhancing the user experience during authentication processes.

### Dashboard

The central hub of the application, the dashboard, is provided as a starting point for developing the main user interface. It's customisable to fit the application's specific requirements, enabling the creation of a personalised and functional dashboard.

## Tailwind CSS Integration

The application is set up with Tailwind CSS from the start, including configuration files for PostCSS and Tailwind itself. These configurations are pre-tuned for optimal performance and customisation, supporting PurgeCSS out-of-the-box to ensure efficient style loading.

Asset compilation, both for development and production, is streamlined with NPM scripts predefined in the application's `package.json`, facilitating easy asset management.

```bash
# To compile CSS/JavaScript for development and watch for changes...
npm run dev

# To compile CSS/JavaScript for production...
npm run build
```

This overview provides insight into the foundational elements of our application, showcasing the custom-built features and configurations that set it apart from other Laravel projects.
