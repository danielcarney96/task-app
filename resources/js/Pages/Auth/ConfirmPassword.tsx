import React, { useEffect } from 'react'
import { Head, useForm } from '@inertiajs/inertia-react'
import route from 'ziggy-js'
import { Guest } from 'Layouts'
import { Button, Input, Label, ValidationErrors } from 'Components'

export default function ConfirmPassword() {
  const { data, setData, post, processing, errors, reset } = useForm({
    password: '',
  })

  useEffect(
    () => () => {
      reset('password')
    },
    [],
  )

  const onHandleChange = (event: React.ChangeEvent<HTMLInputElement>) => {
    setData(event.target.name as 'password', event.target.value)
  }

  const submit = (e: React.SyntheticEvent) => {
    e.preventDefault()

    post(route('password.confirm'))
  }

  return (
    <Guest>
      <Head title="Confirm Password" />

      <div className="mb-4 text-sm text-gray-600">
        This is a secure area of the application. Please confirm your password
        before continuing.
      </div>

      <ValidationErrors errors={errors} />

      <form onSubmit={submit}>
        <div className="mt-4">
          <Label forInput="password" value="Password" />

          <Input
            type="password"
            name="password"
            value={data.password}
            className="mt-1 block w-full"
            isFocused
            handleChange={onHandleChange}
          />
        </div>

        <div className="flex items-center justify-end mt-4">
          <Button className="ml-4" processing={processing}>
            Confirm
          </Button>
        </div>
      </form>
    </Guest>
  )
}
