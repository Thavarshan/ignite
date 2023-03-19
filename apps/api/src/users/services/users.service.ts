import { Injectable } from '@nestjs/common';
import User from '@/users/contracts/user.contract';
import UserDto from '@/users/dto/user.dto';
import UsersRepository from '@/users/repositories/users.repository';
import { isEmail } from '@/utils/helpers';

@Injectable()
export default class UsersService {
    constructor (protected readonly repository: UsersRepository) { }

    public async create (dto: UserDto): Promise<User> {
        return await this.repository.create(dto);
    }

    public async findAll (query?: { [key: string]: any; }): Promise<User[]> {
        return await this.repository.findAll(query);
    }

    public async findOne (id: string): Promise<User> {
        if (isEmail(id)) {
            return await this.repository.findByEmail(id);
        }

        try {
            return await this.repository.findById(id);
        } catch (error) {
            return await this.repository.findByUsername(id);
        }
    }

    public async update (id: string, dto: UserDto): Promise<any> {
        return await this.repository.update(id, dto);
    }

    public async remove (id: string): Promise<void> {
        await this.repository.remove(id);
    }
}
