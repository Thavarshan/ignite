import { Box, Flex, Text } from '@chakra-ui/react';
import { useRouter } from 'next/router';
import Logo from './Logo';
import SidebarNavItem from './SidebarNavItem';

const Sidebar = (props: any) => {
    const router = useRouter();

    return (
        <>
            <Box as='nav' pos='fixed' top='0' left='0' zIndex='sticky' h='full' pb='10' pl='3' overflowX='hidden' overflowY='auto' bg='gray.100' color='inherit' w='60' {...props}>
                <Flex direction='column' justifyContent='between' alignItems='start' h='100%'>
                    <Flex direction='column' w='100%' flex='1'>
                        <Flex px='4' py='5' align='center'>
                            <Logo height={10} width={10} />
                        </Flex>

                        <Flex direction='column' as='nav' fontSize='sm' color='gray.600' aria-label='Main Navigation'>
                            <SidebarNavItem onClick={() => router.push('/tickets')}>Tickets</SidebarNavItem>
                            <SidebarNavItem>Channels</SidebarNavItem>
                            <SidebarNavItem>Teams</SidebarNavItem>
                            <SidebarNavItem>Agents</SidebarNavItem>
                            <SidebarNavItem>Users</SidebarNavItem>
                            <SidebarNavItem>Settings</SidebarNavItem>
                        </Flex>
                    </Flex>

                    <Flex px='4' py='5'>
                        <Box as='footer'>
                            <Text fontSize='xs' color='gray.400'>
                                &copy; {new Date().getFullYear()} Ignite
                            </Text>
                        </Box>
                    </Flex>
                </Flex>
            </Box>
        </>
    );
};

export default Sidebar;
