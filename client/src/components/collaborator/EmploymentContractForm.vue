<template>
  <div class="grid-container">
    <FormInput
      v-model="form.position"
      name="position"
      label="Position"
      type="text"
      required
      :error="form.errors.get('position')"
    />

    <FormInput
      v-model="form.grade"
      name="grade"
      label="Grade"
      type="select"
      :options="grades"
      :error="form.errors.get('grade')"
    />

    <FormInput
      v-model="form.hiring_date"
      name="hiring_date"
      label="Hiring date"
      type="date"
      :error="form.errors.get('hiring_date')"
    />

    <FormInput
      v-model="form.contract_end_date"
      name="contract_end_date"
      label="Contract end date"
      type="date"
      :error="form.errors.get('contract_end_date')"
    />

    <FormInput
      v-model="form.type_of_contract"
      name="type_of_contract"
      label="Type of contract"
      type="select"
      :options="contractTypes"
      :error="form.errors.get('type_of_contract')"
    />

    <FormInput
      v-model="form.department_id"
      name="department_id"
      label="Department"
      type="select"
      required
      :options="departmentOptions"
      :error="form.errors.get('department_id')"
    />
  </div>
</template>

<script>
import FormInput from '@/components/FormInput.vue'
import { GRADES, CONTRACT_TYPES } from '@/constants/dropdowns'

export default {
  name: 'EmploymentContractForm',
  components: {
    FormInput
  },
  props: {
    form: {
      type: Object,
      required: true
    },
    departments: {
      type: Array,
      default: () => []
    }
  },
  data() {
    return {
      grades: GRADES,
      contractTypes: CONTRACT_TYPES
    }
  },
  computed: {
    departmentOptions() {
      return this.departments.map(dept => ({
        value: dept.id,
        label: dept.name
      }))
    }
  }
}
</script>
