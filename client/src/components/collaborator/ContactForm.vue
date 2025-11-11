<template>
  <div class="grid-container mb-4">
    <FormInput
      v-model="form.name"
      name="name"
      label="Full name"
      type="text"
      placeholder="John Doe"
      required
      :error="form.errors.get('name')"
    />

    <FormInput
      v-model="form.email"
      name="email"
      label="Email address"
      type="email"
      placeholder="johndoe@example.com"
      required
      :error="form.errors.get('email')"
    />

    <FormInput
      v-model="form.phone_number"
      name="phone_number"
      label="Phone number"
      type="text"
      placeholder="(+216) 50 123 123"
      required
      :error="form.errors.get('phone_number')"
    />

    <FormInput
      v-model="form.username"
      name="username"
      label="Username"
      type="text"
      placeholder="johndoe"
      :error="form.errors.get('username')"
    />

    <FormInput
      v-model="form.password"
      name="password"
      label="Password"
      type="password"
      :error="form.errors.get('password')"
    />

    <div class="grid">
      <label for="image">{{ isEditing ? 'Change' : 'Set' }} profile image</label>
      <input
        type="file"
        name="profileImage"
        class="form-control-file mt-1"
        id="image"
        @change="$emit('image-change', $event)"
      />
    </div>
  </div>
</template>

<script>
import FormInput from '@/components/FormInput.vue'

export default {
  name: 'ContactForm',
  components: {
    FormInput
  },
  props: {
    form: {
      type: Object,
      required: true
    },
    isEditing: {
      type: Boolean,
      default: false
    }
  },
  emits: ['image-change']
}
</script>
