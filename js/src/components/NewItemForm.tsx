import {FormEvent, useState} from "react";

interface Props {
    // Name "onSubmit" is the same that used in <NewItemForm onSubmit={addTodo} /> so it is auto-wire it.
    onSubmit: (title: string) => void;
}

export default function NewItemForm({ onSubmit }: Props) {

    // Using states for variables [variableName, functionToSet] = useState(initialValue).
    const [newItem, setNewItem] = useState("")

    const handleSubmit = (e: FormEvent<HTMLFormElement>) => {
        e.preventDefault();

        if (newItem === "") return

        onSubmit(newItem)

        setNewItem("");
    };

    return (
        <form onSubmit={handleSubmit} className="new-item-form">
            <div className="form-item">
                <input
                    className="text-field"
                    value={newItem}
                    onChange={event => setNewItem(event.target.value)}
                    type="text"
                    id="item"
                    placeholder="New item name"
                />
            </div>
            <button className="button">Add</button>
        </form>
    )
}
