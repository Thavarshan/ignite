import TeamsService from '@/teams/services/teams.service';
import User from '@/users/contracts/user.contract';
import Roles from '@/users/enums/roles.enum';
import UsersService from '@/users/services/users.service';
import { BadRequestException, Injectable, NotFoundException } from '@nestjs/common';
import _ from 'lodash';
import RegisterUserDto from '../dto/register-user.dto';

@Injectable()
export default class RegisterService {
    constructor (
        protected users: UsersService,
        protected teams: TeamsService
    ) { }

    public async register (dto: RegisterUserDto): Promise<User> {
        await this.determineEmailIsUnique(dto.email);

        const user = await this.createUser(dto);

        await this.assignRole(user, dto);

        await this.createOrAssignTeam(user, dto);

        return user;
    }

    protected async determineEmailIsUnique (email: string): Promise<void> {
        try {
            const user = await this.users.findOne(email);

            if (user) {
                throw new BadRequestException('Email already in use.');
            }
        } catch (error) {
            if (!(error instanceof NotFoundException)) {
                throw error;
            }
        }
    }

    public async createUser (dto: RegisterUserDto): Promise<User> {
        return await this.users.create({
            name: dto.name,
            email: dto.email,
            password: dto.password,
            phone: dto.phone,
            verified: dto.verified || true
        });
    }

    public async assignRole (user: User, dto: RegisterUserDto): Promise<User> {
        if (_.has(dto, 'role')) {
            user.roles = _.concat(user.roles, dto.role);

            await user.save();
        }

        return user;
    }

    public async createOrAssignTeam (user: User, dto: RegisterUserDto): Promise<User> {
        if (_.includes(user.roles, Roles.ADMIN)) {
            const team = await this.teams.create({
                name: `${user.name}'s Team`,
                email: user.email,
                owner: _.get(user, '_id')
            });

            user.team = _.get(team, '_id');
        } else if (_.includes(user.roles, Roles.AGENT)) {
            try {
                const team = await this.teams.findOne(dto.team);

                user.team = team.id;
            } catch (error) {
                user.roles = [Roles.USER];
            }
        }

        await user.save();

        return user;
    }
}
