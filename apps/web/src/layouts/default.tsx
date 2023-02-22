import React from 'react';

const Layout = ({ children }: any) => {
    return (
        <main className="leading-normal antialiased text-gray-600 bg-gray-50 h-screen overflow-x-hidden">
            {children}
        </main>
    );
};

export default Layout;
