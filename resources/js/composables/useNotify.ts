import Notiflix from 'notiflix';

const { Notify } = Notiflix;

if (typeof window !== 'undefined') {
    Notify.init({
        position: 'right-top',
        timeout: 3000,
        clickToClose: true,
        fontFamily: 'Barlow',
    });
}

export const notifySuccess = (message: string) => {
    if (typeof window === 'undefined') return;
    Notify.success(message);
};

export const notifyError = (message: string) => {
    if (typeof window === 'undefined') return;
    Notify.failure(message);
};