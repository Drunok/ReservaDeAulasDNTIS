import { TextField } from '@mui/material';
import { Controller } from 'react-hook-form';

const FechaField = ({ control, minDate, maxDate }) => {
  return (
    <Controller
      name="fecha"
      control={control}
      defaultValue={minDate}
      rules={{ required: true }}
      render={({ field }) => (
        <TextField
          {...field}
          type="date"
          label="Fecha"
          InputLabelProps={{
            shrink: true,
          }}
          inputProps={{
            min: minDate,
            max: maxDate,
          }}
        />
      )}
    />
  );
};

export default FechaField;