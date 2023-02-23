import { Box, Button, Card, CardBody, CardHeader, Heading } from '@chakra-ui/react';
import { getServerSession } from 'next-auth/next';
import { signOut } from 'next-auth/react';
import { authOptions } from '@/pages/api/auth/[...nextauth]';

const Show = ({ user }: any) => {
    return (
        <>
            <Box p={10}>
                <Card>
                    <CardHeader>
                        <Heading size='md'>{user.name}</Heading>
                    </CardHeader>

                    <CardBody>
                        <Button onClick={() => signOut()}>
                            Sign out
                        </Button>
                    </CardBody>
                </Card>
            </Box>
        </>
    );
};

export async function getServerSideProps (context: any) {
    const session: any = await getServerSession(context.req, context.res, authOptions);
    const response = await fetch('http://localhost:3001/user', {
        headers: {
            'Content-Type': 'application/json',
            Authorization: `Bearer ${session.accessToken}`
        }
    });
    const user = await response.json();
    console.log(user);

    return {
        props: { user }
    };
}

export default Show;
