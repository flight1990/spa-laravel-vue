import { createStore } from 'vuex';
import auth from '@/store/modules/auth';
import users from '@/store/modules/users';
import otp from '@/store/modules/otp';

const store = createStore({
    modules: {
        auth,
        users,
        otp
    }
});

export default store
