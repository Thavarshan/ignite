import {
    Controller,
    Get,
    Post,
    Body,
    Patch,
    Param,
    Delete,
    UseGuards,
    HttpCode,
    HttpStatus,
    Response
} from '@nestjs/common';
import TeamService from '@/teams/services/team.service';
import TeamDto from '@/teams/dto/team.dto';
import Team from '@/teams/contracts/team.contract';
import JwtAuthGuard from '@/auth/guards/jwt-auth.guard';

@Controller('teams')
export default class TeamsController {
    constructor (protected readonly service: TeamService) { }

    @HttpCode(HttpStatus.CREATED)
    @UseGuards(JwtAuthGuard)
    @Post()
    public async create (@Body() dto: TeamDto): Promise<Team> {
        return this.service.create(dto);
    }

    @UseGuards(JwtAuthGuard)
    @Get()
    public async findAll (): Promise<{ [key: string]: Team[]; }> {
        const data = await this.service.findAll();

        return { data };
    }

    @UseGuards(JwtAuthGuard)
    @Get(':id')
    public async findOne (@Param('id') id: string): Promise<Team> {
        return await this.service.findOne(id);
    }

    @UseGuards(JwtAuthGuard)
    @Patch(':id')
    public async update (
        @Param('id') id: string,
        @Body() dto: TeamDto
    ): Promise<Team> {
        return await this.service.update(id, dto);
    }

    @HttpCode(HttpStatus.NO_CONTENT)
    @UseGuards(JwtAuthGuard)
    @Delete(':id')
    public async remove (@Param('id') id: string): Promise<void> {
        await this.service.remove(id);
    }
}
