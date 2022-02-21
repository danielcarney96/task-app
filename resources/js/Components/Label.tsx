import React, { ReactNode } from 'react';

interface Props {
    forInput: string;
    value: string;
    className?: string;
    children?: ReactNode;
}

const Label: React.FC<Props> = ({
    forInput,
    value,
    className,
    children,
}: Props) => (
    <label
        htmlFor={forInput}
        className={`block font-medium text-sm text-gray-700 ${className}`}
    >
        {value || children}
    </label>
);

export default Label;
