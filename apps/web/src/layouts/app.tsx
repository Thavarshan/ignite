import {
    Menu,
    MenuButton,
    MenuList,
    MenuItem,
    MenuDivider,
    Avatar,
    Box,
    Button,
    Drawer,
    DrawerContent,
    DrawerOverlay,
    Flex,
    IconButton,
    Input,
    InputGroup,
    InputLeftElement,
    useDisclosure,
    Text
} from '@chakra-ui/react';
import { FiMenu, FiSearch } from 'react-icons/fi';
import Sidebar from '@/components/Sidebar';
import useAuth from '@/hooks/useAuth';
import avatar from '@/lib/Utils/avatar';
import { useRouter } from 'next/router';
import { ChevronDownIcon } from '@chakra-ui/icons';

const Layout = ({ children }: any) => {
    const router = useRouter();
    const sidebar = useDisclosure();
    const { logout, user } = useAuth();

    return (
        <>
            <Box as='section' bg='white' minH='100vh'>
                <Sidebar display={{ base: 'none', md: 'unset' }} />

                <Drawer isOpen={sidebar.isOpen} onClose={sidebar.onClose} placement='left'>
                    <DrawerOverlay />
                    <DrawerContent>
                        <Sidebar w='full' borderRight='none' />
                    </DrawerContent>
                </Drawer>

                <Box ml={{ base: 0, md: 60 }} transition='.3s ease'>
                    <Flex as='header' align='center' justify='space-between' w='full' px='10' bg='white' color='inherit' h='14'>
                        <IconButton aria-label='Menu' display={{ base: 'inline-flex', md: 'none' }} onClick={sidebar.onOpen} icon={<FiMenu />} size='sm' />

                        <InputGroup w='96' display={{ base: 'none', md: 'flex' }} bg='transparent'>
                            <InputLeftElement color='gray.500'>
                                <FiSearch />
                            </InputLeftElement>

                            <Input placeholder='Search for tickets...' rounded='xl' shadow='sm' />
                        </InputGroup>

                        <Flex align='center'>
                            <Menu>
                                <MenuButton as={Button} variant='ghost' size='sm' fontWeight='medium' rounded='lg' rightIcon={<ChevronDownIcon />}>
                                    {user?.team.name}
                                </MenuButton>

                                <MenuList rounded='xl' px='3'>
                                    <Box mb='2'>
                                        <Text fontSize='xs' color='gray.500'>Team</Text>
                                    </Box>
                                    <MenuItem rounded='lg' fontSize='sm' fontWeight='semibold'>Settings</MenuItem>
                                    <MenuItem rounded='lg' fontSize='sm' fontWeight='semibold'>Invite</MenuItem>
                                </MenuList>
                            </Menu>

                            <Menu>
                                <MenuButton>
                                    <Avatar ml='4' size='sm' name={user?.name || 'Ignite'} src={avatar(user?.name || 'Ignite')} cursor='pointer' />
                                </MenuButton>

                                <MenuList rounded='xl' px='3'>
                                    <Box mb='2'>
                                        <Text fontSize='xs' color='gray.500'>Account</Text>
                                    </Box>
                                    <MenuItem onClick={() => router.push(`/users/${user?.username}`)} rounded='lg' as='a' fontSize='sm' fontWeight='semibold'>Profile</MenuItem>
                                    <MenuItem rounded='lg' as='a' fontSize='sm' fontWeight='semibold'>API</MenuItem>
                                    <MenuDivider />
                                    <Box mb='2'>
                                        <Text fontSize='xs' color='gray.500'>Support</Text>
                                    </Box>
                                    <MenuItem rounded='lg' fontSize='sm' fontWeight='semibold'>Help</MenuItem>
                                    <MenuItem rounded='lg' fontSize='sm' fontWeight='semibold'>Docs</MenuItem>
                                    <MenuDivider />
                                    <MenuItem onClick={() => logout()} rounded='lg' fontSize='sm' fontWeight='semibold'>Sign out</MenuItem>
                                </MenuList>
                            </Menu>
                        </Flex>
                    </Flex>

                    <Box as='main' p='10'>{children}</Box>
                </Box>
            </Box>
        </>
    );
};

export default Layout;
