const setToastContent = (title: string, description: string): any => ({
    title,
    description,
    position: 'bottom-right',
    variant: 'solid',
    isClosable: true,
    status: 'error'
} as any);

export default setToastContent;
