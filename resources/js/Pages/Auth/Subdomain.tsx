import React from 'react'
import { Head, Link, useForm } from '@inertiajs/inertia-react'
import route from 'ziggy-js'
import { Button, Input, Label, ValidationErrors } from 'Components'

export interface ISubdomainProps {}

export const Subdomain: React.FC<ISubdomainProps> = (props) => {
  const { data, setData, post, processing, errors, reset } = useForm({
    name: '',
  })

  const onHandleChange = (event: React.ChangeEvent<HTMLInputElement>) => {
    setData(event.target.name as 'name', event.target.value)
  }

  const submit = (e: React.SyntheticEvent) => {
    e.preventDefault()

    post(route('register-subdomain'))
  }

  return (
    <>
      <Head title="Subdomain" />
      <ValidationErrors errors={errors} />

      <form onSubmit={submit}>
        <div>
          <Label forInput="name" value="Name" />

          <Input
            type="text"
            name="name"
            value={data.name}
            className="mt-1 block w-full"
            autoComplete="name"
            isFocused
            handleChange={onHandleChange}
            required
          />
        </div>

        <Button processing={processing}>Submit</Button>
      </form>
    </>
  )
}

export default Subdomain
