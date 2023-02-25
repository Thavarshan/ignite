import { Inject, NotFoundException } from '@nestjs/common';
import _ from 'lodash';
import { Model } from 'mongoose';
import Team from '../contracts/team.contract';
import TeamEnum from '../enums/team.enum';
import TeamDto from '../dto/team.dto';

export default class TeamRepository {
    constructor (
        @Inject(TeamEnum.MODEL) protected model: Model<Team>
    ) { }

    public async create (dto: TeamDto): Promise<Team> {
        return await this.model.create(dto);
    }

    public async findAll (): Promise<Team[]> {
        return await this.model.find().exec();
    }

    public async findById (id: string): Promise<any> {
        try {
            const team = await this.model.findById(id).exec();

            return team;
        } catch (error) {
            throw new NotFoundException('Team not found.');
        }
    }

    public async findBySlug (slug: string): Promise<Team> {
        const team = await this.model.findOne({ slug }).exec();

        if (!team) {
            throw new NotFoundException('Team not found.');
        }

        return team;
    }

    public async findByTeamname (teamname: string): Promise<Team> {
        const team = await this.model.findOne({ teamname }).exec();

        if (!team) {
            throw new NotFoundException('Team not found.');
        }

        return team;
    }

    public async existsByEmail (email: string): Promise<boolean> {
        const results = await this.model.exists({ email }).exec();

        return !_.isNull(results);
    }

    public async update (id: string, dto: TeamDto): Promise<any> {
        return await this.model
            .findOneAndUpdate({ _id: id }, dto, { new: true })
            .exec();
    }

    public async remove (id: string): Promise<void> {
        await this.model.deleteOne({ _id: id }).exec();
    }
}
