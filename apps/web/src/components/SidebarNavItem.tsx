import { Flex } from '@chakra-ui/react';

const SidebarNavItem = (props: any) => {
    const { children, ...rest } = props;

    return (
        <>
            <Flex align='center' px='4' pl='4' py='3' cursor='pointer' color='inherit' _hover={{ bg: 'gray.100', color: 'gray.900' }} role='group' fontWeight='semibold' transition='.15s ease' {...rest}>
                {children}
            </Flex>
        </>
    );
};

export default SidebarNavItem;
