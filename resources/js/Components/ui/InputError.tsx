import { HTMLAttributes } from 'react';

const InputError = ({ message, className = '', ...props }: HTMLAttributes<HTMLParagraphElement> & { message?: string }) => {
    return message ? (
        <p {...props} className={'text-sm text-red-600 dark:text-red-400 ' + className}>
            {message}
        </p>
    ) : null;
}

export { InputError }