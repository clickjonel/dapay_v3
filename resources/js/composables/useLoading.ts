import Notiflix from 'notiflix';

const { Loading } = Notiflix;

if (typeof window !== 'undefined') {
    Loading.init({
        fontFamily: 'Barlow',
    });
}

export const showLoader = () => {
    Loading.pulse('Loading, Please Wait...');
};

export const hideLoader = () => {
    Loading.remove();
};