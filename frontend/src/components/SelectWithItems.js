import { Select, MenuItem } from '@mui/material';

function SelectWithItems({ items, label, ...props }) {
  return (
    <Select variant="outlined" margin="normal" {...props}>
      <MenuItem value="">{label}</MenuItem>
      {items.map((item, index) => (
        <MenuItem key={index} value={item}>
          {item}
        </MenuItem>
      ))}
    </Select>
  );
}

export default SelectWithItems;