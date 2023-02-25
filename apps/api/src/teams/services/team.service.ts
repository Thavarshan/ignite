import { Injectable } from '@nestjs/common';
import Team from '../contracts/team.contract';
import TeamDto from '../dto/team.dto';
import TeamRepository from '../repositories/team.repository';

@Injectable()
export default class TeamService {
    constructor (protected readonly repository: TeamRepository) { }

    public async create (dto: TeamDto): Promise<Team> {
        return await this.repository.create(dto);
    }

    public async findAll (): Promise<Team[]> {
        return await this.repository.findAll();
    }

    public async findOne (id: string): Promise<Team> {
        try {
            return await this.repository.findById(id);
        } catch (error) {
            return await this.repository.findBySlug(id);
        }
    }

    public async update (id: string, dto: TeamDto): Promise<any> {
        return await this.repository.update(id, dto);
    }

    public async remove (id: string): Promise<void> {
        await this.repository.remove(id);
    }
}
