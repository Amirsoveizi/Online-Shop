<script setup>
import { reactive } from 'vue'
import { useRouter } from 'vue-router'
import { mdiAccount, mdiAsterisk } from '@mdi/js'
import SectionFullScreen from '@/admin/components/SectionFullScreen.vue'
import CardBox from '@/admin/components/CardBox.vue'
import FormCheckRadio from '@/admin/components/FormCheckRadio.vue'
import FormField from '@/admin/components/FormField.vue'
import FormControl from '@/admin/components/FormControl.vue'
import BaseButton from '@/admin/components/BaseButton.vue'
import BaseButtons from '@/admin/components/BaseButtons.vue'
import LayoutGuest from '@/admin/layouts/LayoutGuest.vue'

const form = reactive({
  login: '',
  pass: '',
  remember: false,
})

const router = useRouter()

const submit = () => {
  router.push('/admin/dashboard')
}
</script>

<template>
  <LayoutGuest>
    <SectionFullScreen v-slot="{ cardClass }" bg="purplePink">
      <CardBox :class="cardClass" is-form @submit.prevent="submit">
        <FormField label="Login" help="Please enter your login">
          <FormControl
            v-model="form.login"
            :icon="mdiAccount"
            name="login"
            autocomplete="username"
          />
        </FormField>

        <FormField label="Password" help="Please enter your password">
          <FormControl
            v-model="form.pass"
            :icon="mdiAsterisk"
            type="password"
            name="password"
            autocomplete="current-password"
          />
        </FormField>

        <FormCheckRadio
          v-model="form.remember"
          name="remember"
          label="Remember"
          :input-value="true"
        />

        <template #footer>
          <BaseButtons>
            <BaseButton type="submit" color="info" label="Login" />
            <BaseButton to="/dashboard" color="info" outline label="Back" />
          </BaseButtons>
        </template>
      </CardBox>
    </SectionFullScreen>
  </LayoutGuest>
</template>
