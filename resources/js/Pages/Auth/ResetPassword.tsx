import React, { useEffect } from 'react'
import { Head, useForm } from '@inertiajs/inertia-react'
import route from 'ziggy-js'
import { Guest } from 'Layouts'
import { Button, Input, Label, ValidationErrors } from 'Components'

interface Props {
  token: string
  email: string
}

export default function ResetPassword({ token, email }: Props) {
  const { data, setData, post, processing, errors, reset } = useForm({
    token,
    email,
    password: '',
    password_confirmation: '',
  })

  useEffect(
    () => () => {
      reset('password', 'password_confirmation')
    },
    [],
  )

  const onHandleChange = (event: React.ChangeEvent<HTMLInputElement>) => {
    setData(
      event.target.name as
        | 'email'
        | 'password'
        | 'password_confirmation'
        | 'token',
      event.target.value,
    )
  }

  const submit = (e: React.SyntheticEvent) => {
    e.preventDefault()

    post(route('password.update'))
  }

  return (
    <Guest>
      <Head title="Reset Password" />

      <ValidationErrors errors={errors} />

      <form onSubmit={submit}>
        <div>
          <Label forInput="email" value="Email" />

          <Input
            type="email"
            name="email"
            value={data.email}
            className="mt-1 block w-full"
            autoComplete="username"
            handleChange={onHandleChange}
          />
        </div>

        <div className="mt-4">
          <Label forInput="password" value="Password" />

          <Input
            type="password"
            name="password"
            value={data.password}
            className="mt-1 block w-full"
            autoComplete="new-password"
            isFocused
            handleChange={onHandleChange}
          />
        </div>

        <div className="mt-4">
          <Label forInput="password_confirmation" value="Confirm Password" />

          <Input
            type="password"
            name="password_confirmation"
            value={data.password_confirmation}
            className="mt-1 block w-full"
            autoComplete="new-password"
            handleChange={onHandleChange}
          />
        </div>

        <div className="flex items-center justify-end mt-4">
          <Button className="ml-4" processing={processing}>
            Reset Password
          </Button>
        </div>
      </form>
    </Guest>
  )
}
