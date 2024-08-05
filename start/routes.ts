/*
|--------------------------------------------------------------------------
| Routes file
|--------------------------------------------------------------------------
|
| The routes file is used for defining the HTTP routes.
|
*/
import router from '@adonisjs/core/services/router';
import { middleware } from '#start/kernel';

const AuthenticateSessionController = () => import('#controllers/authenticate_session_controller');
const RegisterController = () => import('#controllers/register_controller');

router.on('/').renderInertia('home', { version: 6 });

router
  .get('/login', [AuthenticateSessionController, 'create'])
  .use(middleware.guest());
router
  .post('/login', [AuthenticateSessionController, 'store'])
  .use(middleware.guest());
router
  .get('/register', [RegisterController, 'create'])
  .use(middleware.guest());
router
  .post('/register', [RegisterController, 'store'])
  .use(middleware.guest());
