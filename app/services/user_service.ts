// import { Disk } from '@adonisjs/drive';
import { inject } from '@adonisjs/core';
import User from '#models/user';

@inject()
export default class UserService {
  async create(data: { [key: string]: unknown; }) {
    return await User.create(data);
  }
}
