const avatar = (name: string): string => {
    return `https://ui-avatars.com/api/?name=${encodeURI(name)}&color=7F9CF5&background=EBF4FF`;
};

export default avatar;
