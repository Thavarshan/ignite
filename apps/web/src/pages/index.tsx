import { useRouter } from 'next/router';
import {
    Box,
    Button,
    Modal,
    ModalOverlay,
    ModalContent,
    ModalHeader,
    ModalFooter,
    ModalBody,
    ModalCloseButton,
    useDisclosure,
    Container,
    Table,
    Thead,
    Tbody,
    Tfoot,
    Tr,
    Th,
    Td,
    TableContainer,
} from '@chakra-ui/react';
import { signOut, useSession } from 'next-auth/react';

const Home = () => {
    const { data: session, status }: any = useSession();
    const router = useRouter();
    const { isOpen, onOpen, onClose } = useDisclosure();
    let authenticated = false;
    if (session?.accessToken) {
        authenticated = true;
    }

    return (
        <>
            <Container px='8' py='6' maxWidth='container.xl'>
                <Box mb={6} borderWidth='1px' borderRadius='lg' overflow='hidden'>
                    <h1>Hello</h1>
                </Box>

                <Box mb={6} borderWidth='1px' borderRadius='lg' overflow='hidden'>
                    <TableContainer>
                        <Table variant='simple'>
                            <Thead bgColor='gray.50'>
                                <Tr>
                                    <Th>To convert</Th>
                                    <Th>into</Th>
                                    <Th isNumeric>multiply by</Th>
                                </Tr>
                            </Thead>
                            <Tbody>
                                <Tr>
                                    <Td>inches</Td>
                                    <Td>millimetres (mm)</Td>
                                    <Td isNumeric>25.4</Td>
                                </Tr>
                                <Tr>
                                    <Td>feet</Td>
                                    <Td>centimetres (cm)</Td>
                                    <Td isNumeric>30.48</Td>
                                </Tr>
                                <Tr>
                                    <Td>yards</Td>
                                    <Td>metres (m)</Td>
                                    <Td isNumeric>0.91444</Td>
                                </Tr>
                            </Tbody>
                        </Table>
                    </TableContainer>
                </Box>

                <Button onClick={onOpen}>Open Modal</Button>

                <Button onClick={() => router.push('/auth/login')} colorScheme='blue' ml={3}>Login</Button>

                {authenticated && <Button onClick={() => signOut()} colorScheme='red' variant='outline' ml={3}>Sign out</Button>}

                <Modal isOpen={isOpen} onClose={onClose}>
                    <ModalOverlay />

                    <ModalContent>
                        <ModalHeader>Modal Title</ModalHeader>

                        <ModalCloseButton />

                        <ModalBody>
                            <p>
                                Lorem ipsum dolor sit amet, consectetur adipiscing elit. Ut a aliquam felis. Sed eu lobortis arcu. Nulla pulvinar, quam a vestibulum finibus, lacus libero vulputate enim, vel commodo nibh diam nec purus. Nunc commodo felis ultricies, egestas lacus at, pellentesque nisl. Aliquam erat volutpat. Maecenas quis nunc et nisl euismod porta et eget mi. Ut nibh risus, egestas sit amet interdum non, consectetur et odio.
                            </p>
                        </ModalBody>

                        <ModalFooter>
                            <Button onClick={onClose}>
                                Close
                            </Button>
                        </ModalFooter>
                    </ModalContent>
                </Modal>
            </Container>
        </>
    );
};

export default Home;
