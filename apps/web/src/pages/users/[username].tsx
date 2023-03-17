import Layout from '@/layouts/app';
import Client from '@/lib/Api/Client';
import {
    Box,
    Button,
    chakra,
    Text,
    GridItem,
    SimpleGrid,
    Stack,
    Heading,
    FormControl,
    FormLabel,
    Input,
    Select
} from '@chakra-ui/react';
import { getCookie } from 'cookies-next';
import { GetServerSideProps, NextPage } from 'next';

const Show = ({ user }: any) => {
    return (
        <>
            <Box mt={[10, 0]}>
                <SimpleGrid
                    display={{
                        base: 'initial',
                        md: 'grid'
                    }}
                    columns={{
                        md: 3
                    }}
                    spacing={{
                        md: 6
                    }}
                >
                    <GridItem colSpan={{ md: 1 }}>
                        <Box px={[4, 0]}>
                            <Heading as='h4' size='md'>Profile</Heading>
                            <Text mt={1} fontSize='sm' color='gray.600'>
                                Use a permanent address where you can receive mail.
                            </Text>
                        </Box>
                    </GridItem>
                    <GridItem
                        mt={[5, null, 0]}
                        colSpan={{
                            md: 2
                        }}
                    >
                        <chakra.form
                            method='POST'
                            shadow='base'
                            rounded={[null, 'xl']}
                            overflow={{
                                sm: 'hidden'
                            }}
                        >
                            <Stack
                                px={4}
                                py={5}
                                p={[null, 6]}
                                bg='white'
                                _dark={{
                                    bg: '#141517'
                                }}
                                spacing={6}
                            >
                                <SimpleGrid columns={6} spacing={6}>
                                    <FormControl as={GridItem} colSpan={[6, 3]}>
                                        <FormLabel
                                            htmlFor='name'
                                            fontSize='sm'
                                            fontWeight='md'
                                            color='gray.700'
                                            _dark={{
                                                color: 'gray.50'
                                            }}
                                        >
                                            Name
                                        </FormLabel>
                                        <Input
                                            type='text'
                                            name='name'
                                            id='name'
                                            autoComplete='given-name'
                                            mt={1}
                                            focusBorderColor='blue.500'
                                            shadow='sm'
                                            value={user?.name}
                                            w='full'
                                            rounded='xl'
                                        />
                                    </FormControl>

                                    <FormControl as={GridItem} colSpan={[6, 3]}>
                                        <FormLabel
                                            htmlFor='email_address'
                                            fontSize='sm'
                                            fontWeight='md'
                                            color='gray.700'
                                            _dark={{
                                                color: 'gray.50'
                                            }}
                                        >
                                            Email address
                                        </FormLabel>
                                        <Input
                                            type='text'
                                            name='email_address'
                                            id='email_address'
                                            autoComplete='email'
                                            mt={1}
                                            focusBorderColor='blue.500'
                                            shadow='sm'
                                            w='full'
                                            rounded='xl'
                                        />
                                    </FormControl>

                                    <FormControl as={GridItem} colSpan={[6, 3]}>
                                        <FormLabel
                                            htmlFor='country'
                                            fontSize='sm'
                                            fontWeight='md'
                                            color='gray.700'
                                            _dark={{
                                                color: 'gray.50'
                                            }}
                                        >
                                            Country / Region
                                        </FormLabel>
                                        <Select
                                            id='country'
                                            name='country'
                                            autoComplete='country'
                                            placeholder='Select option'
                                            mt={1}
                                            focusBorderColor='blue.500'
                                            shadow='sm'
                                            w='full'
                                            rounded='xl'
                                        >
                                            <option>United States</option>
                                            <option>Canada</option>
                                            <option>Mexico</option>
                                        </Select>
                                    </FormControl>

                                    <FormControl as={GridItem} colSpan={6}>
                                        <FormLabel
                                            htmlFor='line1'
                                            fontSize='sm'
                                            fontWeight='md'
                                            color='gray.700'
                                        >
                                            Street address
                                        </FormLabel>
                                        <Input
                                            type='text'
                                            name='line1'
                                            id='line1'
                                            autoComplete='street-address'
                                            mt={1}
                                            focusBorderColor='blue.500'
                                            shadow='sm'
                                            w='full'
                                            rounded='xl'
                                        />
                                    </FormControl>

                                    <FormControl as={GridItem} colSpan={[6, 6, null, 2]}>
                                        <FormLabel
                                            htmlFor='city'
                                            fontSize='sm'
                                            fontWeight='md'
                                            color='gray.700'
                                            _dark={{
                                                color: 'gray.50'
                                            }}
                                        >
                                            City
                                        </FormLabel>
                                        <Input
                                            type='text'
                                            name='city'
                                            id='city'
                                            autoComplete='city'
                                            mt={1}
                                            focusBorderColor='blue.500'
                                            shadow='sm'
                                            w='full'
                                            rounded='xl'
                                        />
                                    </FormControl>

                                    <FormControl as={GridItem} colSpan={[6, 3, null, 2]}>
                                        <FormLabel
                                            htmlFor='state'
                                            fontSize='sm'
                                            fontWeight='md'
                                            color='gray.700'
                                            _dark={{
                                                color: 'gray.50'
                                            }}
                                        >
                                            State / Province
                                        </FormLabel>
                                        <Input
                                            type='text'
                                            name='state'
                                            id='state'
                                            autoComplete='state'
                                            mt={1}
                                            focusBorderColor='blue.500'
                                            shadow='sm'
                                            w='full'
                                            rounded='xl'
                                        />
                                    </FormControl>

                                    <FormControl as={GridItem} colSpan={[6, 3, null, 2]}>
                                        <FormLabel
                                            htmlFor='postal_code'
                                            fontSize='sm'
                                            fontWeight='md'
                                            color='gray.700'
                                            _dark={{
                                                color: 'gray.50'
                                            }}
                                        >
                                            ZIP / Postal
                                        </FormLabel>
                                        <Input
                                            type='text'
                                            name='postal_code'
                                            id='postal_code'
                                            autoComplete='postal-code'
                                            mt={1}
                                            focusBorderColor='blue.500'
                                            shadow='sm'
                                            w='full'
                                            rounded='xl'
                                        />
                                    </FormControl>
                                </SimpleGrid>
                            </Stack>
                            <Box
                                px={{
                                    base: 4,
                                    sm: 6
                                }}
                                py={3}
                                bg='gray.50'
                                _dark={{
                                    bg: '#121212'
                                }}
                                textAlign='right'
                            >
                                <Button
                                    type='submit'
                                    colorScheme='blue'
                                    rounded='xl'
                                    _focus={{
                                        shadow: ''
                                    }}
                                    fontWeight='md'
                                >
                                    Save
                                </Button>
                            </Box>
                        </chakra.form>
                    </GridItem>
                </SimpleGrid>
            </Box>
        </>
    );
};

export const getServerSideProps: GetServerSideProps = async ({ req, res, params }) => {
    const token = getCookie('token', { req, res });
    const client = new Client(token as string);
    const user = await client
        .resources('users')
        .get(params?.username as string);

    return { props: { user } };
};

Show.getLayout = function getLayout (page: NextPage) {
    return (<Layout>{page}</Layout>);
};

export default Show;
