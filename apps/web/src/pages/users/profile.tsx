/* eslint-disable no-console */
import useAuth from '@/hooks/useAuth';
import { Box, Button, Card, CardBody, CardHeader, Heading } from '@chakra-ui/react';

const Profile = () => {
    const { user, logout } = useAuth({ middleware: 'auth' });

    return (
        <>
            <Box p={10}>
                <Card>
                    <CardHeader>
                        <Heading size='md'>{user?.name}</Heading>
                    </CardHeader>

                    <CardBody>
                        <Button onClick={() => logout()}>
                            Sign out
                        </Button>
                    </CardBody>
                </Card>
            </Box>
        </>
    );
};

export default Profile;
