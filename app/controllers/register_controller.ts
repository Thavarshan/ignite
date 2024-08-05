import type { HttpContext } from '@adonisjs/core/http';
import { inject } from '@adonisjs/core';
import UserService from '#services/user_service';

export default class RegisterController {
  /**
   * Show the registration form
   */
  async create({ inertia }: HttpContext) {
    return inertia.render('auth/register');
  }

  /**
   * Create a new user
   */
  @inject()
  async store({ request, auth, response }: HttpContext, service: UserService) {
    const { email, password } = request.only(['email', 'password']);

    const user = await service.create({ email, password });

    await auth.use('web').login(user);

    response.redirect('/discussions');
  }
}
