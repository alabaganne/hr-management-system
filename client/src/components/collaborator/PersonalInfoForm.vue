<template>
  <div class="grid-container">
    <FormInput
      v-model="form.date_of_birth"
      name="date_of_birth"
      label="Date of birth"
      type="date"
      :error="form.errors.get('date_of_birth')"
    />

    <FormInput
      v-model="form.address"
      name="address"
      label="Address"
      type="text"
      :error="form.errors.get('address')"
    />

    <FormInput
      v-model="form.civil_status"
      name="civil_status"
      label="Civil status"
      type="select"
      :options="civilStatuses"
      :error="form.errors.get('civil_status')"
    />

    <FormInput
      v-model="form.gender"
      name="gender"
      label="Gender"
      type="select"
      :options="genders"
      :error="form.errors.get('gender')"
    />

    <FormInput
      v-model="form.id_card_number"
      name="id_card_number"
      label="ID Card Number"
      type="text"
      :error="form.errors.get('id_card_number')"
    />

    <FormInput
      v-model="form.nationality"
      name="nationality"
      label="Nationality"
      type="text"
      :error="form.errors.get('nationality')"
    />

    <FormInput
      v-model="form.university"
      name="university"
      label="University"
      type="text"
      :error="form.errors.get('university')"
    />

    <FormInput
      v-model="form.history"
      name="history"
      label="History"
      type="text"
      :error="form.errors.get('history')"
    />

    <FormInput
      v-model="form.experience_level"
      name="experience_level"
      label="Experience level (years)"
      type="number"
      :error="form.errors.get('experience_level')"
    />

    <FormInput
      v-model="form.source"
      name="source"
      label="Source"
      type="text"
      :error="form.errors.get('source')"
    />
  </div>
</template>

<script>
import FormInput from '@/components/FormInput.vue'
import { CIVIL_STATUSES, GENDERS } from '@/constants/dropdowns'

export default {
  name: 'PersonalInfoForm',
  components: {
    FormInput
  },
  props: {
    form: {
      type: Object,
      required: true
    }
  },
  data() {
    return {
      civilStatuses: CIVIL_STATUSES,
      genders: GENDERS
    }
  }
}
</script>
