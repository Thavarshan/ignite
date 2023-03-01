type OnSubmitOptions = {
    onSuccess?: (_response: any) => any,
    onFail?: (_error: any) => any,
    onFinish?: (_response?: any) => any;
};

export default OnSubmitOptions;
