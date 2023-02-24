import { Injectable } from '@nestjs/common';
import User from '@/users/contracts/user.contract';
import UserDto from '@/users/dto/user.dto';
import UserRepository from '@/users/repositories/user.repository';

@Injectable()
export default class UserService {
    constructor (protected readonly repository: UserRepository) { }

    public async create (dto: UserDto): Promise<User> {
        return await this.repository.create(dto);
    }

    public async findAll (): Promise<User[]> {
        return await this.repository.findAll();
    }

    public async findOne (id: string): Promise<User> {
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
