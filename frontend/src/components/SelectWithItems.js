import { TextField, MenuItem } from '@mui/material';

function SelectWithItems({ items, label, value, ...props }) {
  return (
    <TextField
      select
      variant="outlined"
      margin="normal"
      label={label}
      value={value}
      {...props}
    >
      {items.map((item, index) => (
        <MenuItem key={index} value={item}>
          {item}
        </MenuItem>
      ))}
    </TextField>
  );
}

export default SelectWithItems;